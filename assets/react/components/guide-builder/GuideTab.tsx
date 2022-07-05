import { useState } from 'react';
import { toast } from 'react-toastify';
import DraggableTab from './DraggableTab';
import { GuideTabType } from '@shared/types/guide_types';
import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import { EditTabModal } from './tabs/EditTabModal';
import { htmlEntityDecode, objectIsEmpty } from '@utility/Utility';
import { useUpdateTab } from '@hooks/useUpdateTab';
import { useDeleteTab } from '@hooks/useDeleteTab';

type GuideTabProps = {
    tab: GuideTabType
}

export const GuideTab = ({ tab }: GuideTabProps) => {
    const { subjectId, currentTab } = useGuideTabContainer() as GuideTabContainerType;
    const [showSettings, setShowSettings] = useState<boolean>(false);
    const [isSaving, setIsSaving] = useState<boolean>(false);

    const updateTabMutation = useUpdateTab(subjectId);
    const deleteTabMutation = useDeleteTab(subjectId);

    const isCurrentTab = currentTab.tabIndex === tab.tabIndex;

    const updateTab = (changes: Record<string, any>) => {
        setIsSaving(true);

        updateTabMutation.mutate({
                tabUUID: currentTab.id,
                tabIndex: currentTab.tabIndex,
                data: changes,
                optimisticResult: {
                    ...currentTab,
                    'label': changes['label'] ?? currentTab['label'],
                    'externalUrl': changes['externalUrl'] ?? currentTab['externalUrl'],
                    'visibility': changes['visibility'] ?? currentTab['visibility']
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

    const handleUpdateTab = (evt: React.FormEvent<HTMLFormElement>) => {
        evt.preventDefault();
        
        const form = evt.currentTarget;

        if (form.checkValidity() === false) {
          evt.stopPropagation();
          return;
        }

        const formData = new FormData(evt.currentTarget);
        const changes: Record<string, any> = {};
        const newLabel = htmlEntityDecode(formData.get('label') as string);
        const newVisibility = ((formData.get('visibility') as string) === '1');
        const newExternalUrl = htmlEntityDecode(formData.get('externalUrl') as string);

        // Check for any changes in tab data
        if (newLabel !== currentTab.label) changes['label'] = newLabel;
        if (newExternalUrl !== currentTab.externalUrl) changes['externalUrl'] = newExternalUrl;
        if (newVisibility !== currentTab.visibility) changes['visibility'] = newVisibility;

        if (!objectIsEmpty(changes)) {
            updateTab(changes);
        } else {
            setShowSettings(false);
        }
    }

    const handleTabDelete = (evt: React.MouseEvent<HTMLButtonElement>) => {
        evt.preventDefault();
        console.log('handleTabDelete evt:', evt);
    }
    
    return (
        <>
            <DraggableTab tabId={tab.id} tabIndex={tab.tabIndex} label={tab.label}
                    active={isCurrentTab} onClick={() => setShowSettings(!showSettings)} />
            
            {isCurrentTab && <EditTabModal currentTab={tab} show={showSettings} onHide={() => setShowSettings(false)}
                onSubmit={handleUpdateTab} deleteButtonOnClick={handleTabDelete} savingChanges={isSaving}
            />}
        </>
    )
}