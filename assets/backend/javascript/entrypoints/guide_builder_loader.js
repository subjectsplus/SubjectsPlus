import React from 'react';
import ReactDOM from 'react-dom';
import GuideBuilder from '../../../react/components/guide-builder/GuideBuilder.js';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';

// TODO: Revisit this method of gathering the guide id
// possibly without relying on the client window location
const path = window.location.pathname;
const subjectId = path.split("/").pop();

// Create a client
const queryClient = new QueryClient();

ReactDOM.render(
    <QueryClientProvider client={queryClient}>
        <GuideBuilder subjectId={subjectId} />
        <ReactQueryDevtools initialIsOpen={false} position="top-right" />
    </QueryClientProvider>, 
    document.getElementById('guide-builder-container')
);