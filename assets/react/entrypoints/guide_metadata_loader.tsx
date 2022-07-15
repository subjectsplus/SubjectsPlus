import ReactDOM from 'react-dom';
import ErrorBoundary from '@components/shared/ErrorBoundary';
import { GuideMetadata } from '@components/guide-metadata/GuideMetadata';
import { QueryClientProvider } from 'react-query';
import { queryClient } from '@utility/queryClient';

const container = document.getElementById('guide-metadata-container');

if (container) {
    const subjectId = container.getAttribute('data-subject-id');
    
    if (subjectId) {
        const numericSubjectId = Number(subjectId);

        if (!isNaN(numericSubjectId)) {
            const guideMetadata = (<GuideMetadata subjectId={numericSubjectId} />);

            ReactDOM.render(
                <QueryClientProvider client={queryClient}>
                    {/* Only use ErrorBoundary in production environment */}
                    {process.env.NODE_ENV === 'development' ? guideMetadata :
                        (<ErrorBoundary>
                            {guideMetadata}
                        </ErrorBoundary>)
                    }
                </QueryClientProvider>, container
            );
        } else {
            console.error('Failed to load guide metadata component; subjectId provided is not valid.');
        }
    } else {
        console.error('Failed to load guide metadata component; subjectId not found.');
    }
} else {
    console.error('Failed to load guide metadata component; container not found.');
}