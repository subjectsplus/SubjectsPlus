import React from 'react';
import ReactDOM from 'react-dom';
import ErrorBoundary from '#components/shared/ErrorBoundary';
import { QueryClient, QueryClientProvider } from 'react-query';
import MediaBrowser from '#components/media-browser/MediaBrowser';
import { ReactQueryDevtools } from 'react-query/devtools';

// Create a client
const queryClient = new QueryClient();

// Construct the MediaBrowser component
const mediaBrowser = (<MediaBrowser />);

ReactDOM.render(
<QueryClientProvider client={queryClient}>
    {/* Only use ErrorBoundary in production environment */}
    {process.env.NODE_ENV === 'development' ? mediaBrowser :
        (<ErrorBoundary>
            {mediaBrowser}    
        </ErrorBoundary>)
    }
    <ReactQueryDevtools initialIsOpen={false} position="top-left" />
</QueryClientProvider>,
    document.getElementById('media-browser-container')
);