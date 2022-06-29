import ReactDOM from 'react-dom';
import ErrorBoundary from '#components/shared/ErrorBoundary';
import GuideMetadata from '#components/guide-metadata/GuideMetadata';
import { QueryClient, QueryClientProvider } from 'react-query';

// TODO: Revisit this method of gathering the guide id
// possibly without relying on the client window location
const path = window.location.pathname;
const subjectId = path.split("/").pop();

// Create a client
const queryClient = new QueryClient();

// Construct the guide builder component
const guideMetadata = (<GuideMetadata subjectId={subjectId} />);

ReactDOM.render(
    <QueryClientProvider client={queryClient}>
        {/* Only use ErrorBoundary in production environment */}
        {process.env.NODE_ENV === 'development' ? guideMetadata :
            (<ErrorBoundary>
                {guideMetadata}    
            </ErrorBoundary>)
        }
    </QueryClientProvider>, 
    document.getElementById('guide-metadata-container')
);