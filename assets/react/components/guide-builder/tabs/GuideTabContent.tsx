import Tab from 'react-bootstrap/Tab';
import { GuideTabType } from '@shared/types/guide_types';
import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import SectionContainer from '../SectionContainer';

type GuideTabContentProps = {
    tab: GuideTabType,
}

export const GuideTabContent = ({ tab }: GuideTabContentProps) => {
    const { currentTab } = useGuideTabContainer() as GuideTabContainerType;
    const isCurrentTab = currentTab.id === tab.id;

    return (
        <Tab.Pane id={'guide-tabs-tabpane-' + tab.tabIndex} eventKey={tab.tabIndex} 
            aria-labelledby={'guide-tabs-tab-' + tab.tabIndex} active={isCurrentTab}>
                {isCurrentTab && <SectionContainer tabId={tab.id} />}
        </Tab.Pane>
    );
}