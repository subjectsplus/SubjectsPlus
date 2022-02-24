import React, { Component } from 'react';

export default class SearchBar extends Component {

    constructor(props) {
        super(props);

        this.ignoreEnterKey = this.ignoreEnterKey.bind(this);
        this.preventDefault = this.preventDefault.bind(this);
    }

    ignoreEnterKey(evt) {
        if (evt.keyCode === 13) {
            this.preventDefault(evt);
        }
    }

    preventDefault(evt) {
        evt.preventDefault();
    }

    render() {
        return (
            <form action="#" onSubmit={this.preventDefault}>
                {/* Label is for accessibility purposes, will not be visible */}
                <div className="mb-2">
                    <label htmlFor={this.props.id} className="form-label">
                        <span className="visually-hidden">{this.props.placeholder}</span>
                    </label>
                    <input
                        type="text"
                        id={this.props.id}
                        placeholder= {this.props.placeholder}
                        onChange={this.props.onChange}
                        className={this.props.className}
                        onKeyDown={this.ignoreEnterKey}
                    />
                </div>
            </form>
        )
    }
}