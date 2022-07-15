import { useState } from 'react';
import { toast } from 'react-toastify';
import DraggableTab from './DraggableTab';
import { GuideTabType } from '@shared/types/guide_types';
import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import { EditTabModal } from './EditTabModal';
import { htmlEntityDecode, objectIsEmpty } from '@utility/Utility';
import { useUpdateTab } from '@hooks/useUpdateTab';
import { useDeleteTab } from '@hooks/useDeleteTab';
import { GuideTabFormInputs } from '@shared/types/guide_form_types';

type GuideTabProps = {
    tab: GuideTabType
}

export const GuideTab = ({ tab }: GuideTabProps) => {
    const { subjectId, currentTab, activeKey, setActiveKey } = useGuideTabContainer() as GuideTabContainerType;
    const [showSettings, setShowSettings] = useState<boolean>(false);
    const [isSaving, setIsSaving] = useState<boolean>(false);

    const updateTabMutation = useUpdateTab(subjectId);
    const deleteTabMutation = useDeleteTab(subjectId);

    const isCurrentTab = currentTab?.id === tab.id;

    const updateTab = (changes: Record<string, any>) => {
        setIsSaving(true);

        updateTabMutation.mutate({
                tabUUID: tab.id,
                tabIndex: tab.tabIndex,
                data: changes,
                optimisticResult: {
                    ...tab,
                    'label': changes['label'] ?? tab['label'],
                    'externalUrl': changes['externalUrl'] ?? tab['externalUrl'],
                    'visibility': changes['visibility'] ?? tab['visibility']
                }
            }, {
                onSettled: () => {
                    setShowSettings(false);
                    setIsSaving(false);
                },
                onError: () => {
                    toast.error('Error has occurred. Failed to update tab!');
                }
            }
        );
    }

    const handleUpdateTab = (data: GuideTabFormInputs) => {
        const changes: Record<string, any> = {};
        const newLabel = htmlEntityDecode(data['label']);
        const newVisibility = (data['visibility'] === '1');
        const newExternalUrl = htmlEntityDecode(data['externalUrl']);

        if (newLabel !== tab.label) changes['label'] = newLabel;

        if (newExternalUrl.trim() === '' && tab.externalUrl !== null) {
            // new external url is empty, therefore set to null
            changes['externalUrl'] = null;
        } else if (newExternalUrl !== tab.externalUrl) {
            changes['externalUrl'] = newExternalUrl;
        }

        if (newVisibility !== tab.visibility) changes['visibility'] = newVisibility;

        if (!objectIsEmpty(changes)) {
            updateTab(changes);
        } else {
            setShowSettings(false);
        }
    }

    const deleteTab = () => {
        let newActiveKey = 0;

        if (activeKey !== 0) {
            newActiveKey = activeKey - 1;
        }

        setActiveKey(newActiveKey);

        deleteTabMutation.mutate({
            tabUUID: tab.id
        }, {
            onError: () => {
                toast.error('Error has occurred. Failed to delete tab!');
            }
        });

        setShowSettings(false);
    }

    const handleTabDelete = (evt: React.MouseEvent<HTMLButtonElement>) => {
        evt.preventDefault();
        deleteTab();
    }
    
    return (
        <>
            <DraggableTab tabId={tab.id} tabIndex={tab.tabIndex} label={tab.label}
                    active={isCurrentTab} settingsButtonOnClick={() => setShowSettings(!showSettings)} />
            
            {isCurrentTab && <EditTabModal currentTab={tab} show={showSettings} onHide={() => setShowSettings(false)}
                onSubmit={handleUpdateTab} deleteButtonOnClick={handleTabDelete} savingChanges={isSaving}
            />}
        </>
    )
}