import ReactDOM from 'react-dom';
import { QueryClientProvider } from 'react-query';
import { queryClient } from '@utility/queryClient';
import ErrorBoundary from '@components/shared/ErrorBoundary';
import { MediaBrowser } from '@components/media-browser/MediaBrowser';

// Construct the MediaBrowser component
const container = document.getElementById('media-browser-container');

if (container) {
    const staffId = container.getAttribute('data-staff-id');
    
    if (staffId) {
        const numericStaffId = Number(staffId);

        if (!isNaN(numericStaffId)) {
            const mediaBrowser = (<MediaBrowser staffId={numericStaffId} />);

            ReactDOM.render(
                <QueryClientProvider client={queryClient}>
                    {/* Only use ErrorBoundary in production environment */}
                    {process.env.NODE_ENV === 'development' ? mediaBrowser :
                        (<ErrorBoundary>
                            {mediaBrowser}    
                        </ErrorBoundary>)
                    }
                </QueryClientProvider>, container
            );
        } else {
            console.error('Failed to load media browser component; staffId provided is not valid.');
        }
    } else {
        console.error('Failed to load media browser component; staffId not found.');
    }
} else {
    console.error('Failed to load media browser component; container not found.');
}