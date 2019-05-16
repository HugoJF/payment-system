import React, {Component} from 'react';
import * as Sentry from '@sentry/browser';
import {enableSentry} from "../../constants/variables";

class ErrorBoundary extends Component {
    constructor(props) {
        super(props);
        this.state = {error: null, eventId: null};
    }

    componentDidCatch(error, errorInfo) {
        this.setState({error});
        if (enableSentry) {
            Sentry.withScope(scope => {
                scope.setExtras(errorInfo);
                const eventId = Sentry.captureException(error);
                this.setState({eventId})
            });
        }
    }

    render() {
        if (this.state.error) {
            //render fallback UI
            return (
                <a onClick={() => Sentry.showReportDialog({eventId: this.state.eventId})}>Report feedback</a>
            );
        } else {
            //when there's not an error, render children untouched
            return this.props.children;
        }
    }
}

export default ErrorBoundary;