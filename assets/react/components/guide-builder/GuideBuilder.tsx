import { ToastContainer } from 'react-toastify';
import { GuideTabContainerProvider } from '@context/GuideTabContainerContext';
import { GuideTabContainer } from './tabs/GuideTabContainer';

type GuideBuilderProps = {
    subjectId: number
}

export const GuideBuilder = ({ subjectId }: GuideBuilderProps) => {
    return (
        <div id="guide-builder">
            <GuideTabContainerProvider subjectId={subjectId}>
                <GuideTabContainer />
            </GuideTabContainerProvider>
            <ToastContainer />
        </div>
    );
}