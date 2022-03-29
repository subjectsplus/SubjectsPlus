import React from 'react';
import Error from './Error';

export default class ErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false };
        this.attemptRetry = this.attemptRetry.bind(this);
    }
    
    static getDerivedStateFromError(error) {
        // Update state so the next render will show the fallback UI.
        return { hasError: true };
    }

    attemptRetry() {
        this.setState({
            hasError: false
        })
    }

    render() {
        if (this.state.hasError) {
            // You can render any custom fallback UI      
            return <Error onRetry={this.attemptRetry} />;    
        }

        return this.props.children; 
    }
}