import { Component } from 'react';

export default class Notification extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return (
            <Toast show={this.props.visible} onClose={this.props.onClose}>
                <Toast.Header>
                    {this.props.title}
                </Toast.Header>
                <Toast.Body>{this.props.body}</Toast.Body>
            </Toast>
        );
    }
}
