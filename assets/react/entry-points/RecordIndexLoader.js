import React from 'react';
import ReactDOM from 'react-dom';
import ErrorBoundary from '#components/shared/ErrorBoundary';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import RecordIndexContainer from "#components/record/RecordIndexContainer";

// Create a client
const queryClient = new QueryClient();

// Construct the guide builder component
const recordIndexContainer = (<RecordIndexContainer />);

ReactDOM.render(
    <QueryClientProvider client={queryClient}>
        {/* Only use ErrorBoundary in production environment */}
        {process.env.NODE_ENV === 'development' ? recordIndexContainer :
            (<ErrorBoundary>
                {recordIndexContainer}
            </ErrorBoundary>)
        }
        <ReactQueryDevtools initialIsOpen={false} position="top-left" />
    </QueryClientProvider>,
    document.getElementById('record-index-container')
);
