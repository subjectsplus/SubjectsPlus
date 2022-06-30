import ReactDOM from 'react-dom';
import ErrorBoundary from '#components/shared/ErrorBoundary';
import GuideBuilder from '#components/guide-builder/GuideBuilder';
import { QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';

// TODO: Revisit this method of gathering the guide id
// possibly without relying on the client window location
const path = window.location.pathname;
const subjectId = path.split("/").pop();

// Create a client
const queryClient = window.queryClient;

// Construct the guide builder component
const guideBuilder = (<GuideBuilder subjectId={subjectId} />);

ReactDOM.render(
    <QueryClientProvider client={queryClient}>
        {/* Only use ErrorBoundary in production environment */}
        {process.env.NODE_ENV === 'development' ? guideBuilder :
            (<ErrorBoundary>
                {guideBuilder}    
            </ErrorBoundary>)
        }
        <ReactQueryDevtools initialIsOpen={false} position="top-left" />
    </QueryClientProvider>, 
    document.getElementById('guide-builder-container')
);