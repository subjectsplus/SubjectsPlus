import ReactDOM from 'react-dom';
import ErrorBoundary from '@components/shared/ErrorBoundary';
import GuideBuilder from '@components/guide-builder/GuideBuilder';
import { QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import { queryClient } from '@utility/queryClient';

// Construct the guide builder component
const container = document.getElementById('guide-builder-container');

if (container) {
    const subjectId = container.getAttribute('data-subject-id');
    
    if (subjectId) {
        const numericSubjectId = Number(subjectId);

        if (!isNaN(numericSubjectId)) {
            const guideBuilder = (<GuideBuilder subjectId={subjectId} />);

            ReactDOM.render(
                <QueryClientProvider client={queryClient}>
                    {/* Only use ErrorBoundary in production environment */}
                    {process.env.NODE_ENV === 'development' ? guideBuilder :
                        (<ErrorBoundary>
                            {guideBuilder}
                        </ErrorBoundary>)
                    }
                </QueryClientProvider>, container
            );
        } else {
            console.error('Failed to load guide builder component; subjectId provided is not valid.');
        }
    } else {
        console.error('Failed to load guide builder component; subjectId not found.');
    }
} else {
    console.error('Failed to load guide builder component; container not found.');
}