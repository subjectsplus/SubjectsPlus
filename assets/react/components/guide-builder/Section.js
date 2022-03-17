import React, { Component } from 'react';
import Pluslet from './Pluslet';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

export default class Section extends Component {
    apiLink = '/api/sections/{sectionId}/pluslets';

    constructor(props) {
        super(props);

        this.state = {
            pluslets: null,
            isErrored: false,
            loading: false,
        };
    }

    componentDidMount() {
        this.getPluslets();
    }

    getAPILink() {
        return this.apiLink.replace('{sectionId}', 
            this.props.sectionId);
    }

    getPluslets() {
        // formulate the results api link for guide
        var resLink = this.getAPILink();

        // fetch api results
        this.setState({
            loading: true,
        }, () => fetch(resLink)
            .then(response => {
                if (response.ok) {
                    return response.json();
                }

                this.setState({
                    isErrored: true
                });
            })
            .then(results => {
                this.setState({
                    pluslets: results["hydra:member"],
                    isErrored: false,
                    loading: false
                });
            }
            )
            .catch(err => {
                console.error(err);
                this.setState({
                    isErrored: true
                });
            })
        );
    }

    render() {
        let pluslets = [];

        if (this.state.pluslets) {
            pluslets = this.state.pluslets.map((pluslet, index) => {
                return (<Pluslet key={pluslet.plusletId} plusletId={pluslet.plusletId} plusletRow={index} />)
            });
        }

        if (this.state.isErrored) {
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else if (this.state.loading) {
            return (<p>Loading Pluslets...</p>);
        } else {
            return (
                <Droppable type="pluslet" key={this.props.sectionId.toString()} style={{ transform: "none" }} droppableId={this.props.sectionId.toString()} direction="vertical">
                    {(provided) => (
                        <div className="guide-section" {...provided.droppableProps} ref={provided.innerRef}
                            style={{
                                border: '2px solid black',
                                height: '500px',
                                width: '750px',
                            }}>
                            {pluslets}
                            {provided.placeholder}
                        </div>
                    )}
                </Droppable>
            );
        }
    }
}