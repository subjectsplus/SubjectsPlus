import React, { Component } from 'react';

export default class SearchBar extends Component {

    render() {
        return (
            
            <form action="#">
                {/* Label is for accessibility purposes, will not be visible */}
                <label htmlFor={this.props.id}>
                    <span className="visually-hidden">{this.props.placeholder}</span>
                </label>
                <input
                    type="text"
                    id={this.props.id}
                    placeholder= {this.props.placeholder}
                    onChange={this.props.onChange}
                />
            </form>
        )
    }
}