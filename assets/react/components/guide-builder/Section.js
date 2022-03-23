import React, { useMemo } from 'react';
import Pluslet from './Pluslet';
import { useFetchPluslets } from '#api/guide/PlusletAPI';
import { Draggable, Droppable } from 'react-beautiful-dnd';
import Container from 'react-bootstrap/Container';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';

function Section({ sectionId, layout, sectionIndex }) {
    const {isLoading, isError, data, error} = useFetchPluslets(sectionId);

    const padding = 16;

    const getSectionDraggableStyle = (isDragging, draggableStyle) => ({
        padding: `${padding * 2}px`,
        position: 'relative',
        marginBottom: `${padding}px`,
        background: isDragging ? "lightgreen" : "grey",
        ...draggableStyle
      });

    const getSectionDroppableStyle = (isDraggingOver) => ({
        background: isDraggingOver ? "lightblue" : "lightgrey",
        padding: padding,
        width: 200
    });

    const sectionAnchorStyle = {
        position: 'absolute',
        display: 'block',
        border: '1px solid #000',
        width: '50px',
        height: '20px',
        lineHeight: '20px',
        right: 0,
        top: 0,
        marginTop: '-10px',
        zindex: 1
    }

    const generateContainer = (pluslets) => {
        const splitLayout = layout.split('-');
        
        const rows = splitLayout.map(size => {

            if (Number(size) !== 0) {
                return (
                    <Row>
                        <Col lg={Number(size)}>

                        </Col>
                    </Row>
                )
            }
        });

    }

    const sectionContent = useMemo(() => {
        if (isLoading) {
            return (<p>Loading Pluslets...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            const pluslets = data.map((pluslet, index) =>
                <Pluslet key={pluslet.plusletId} plusletId={pluslet.plusletId} plusletRow={index} />
            );

            return (
                <Draggable type="section" draggableId={'section-' + sectionIndex} index={sectionIndex}>
                    {(provided, snapshot) => (
                        <div ref={provided.innerRef} {...provided.draggableProps}
                            style={getSectionDraggableStyle(snapshot.isDragging, provided.draggableProps.style)}>
                            <span style={sectionAnchorStyle}>
                                <span className="drag-handle" {...provided.dragHandleProps}>
                                    <i className="fas fa-arrows-alt"></i>
                                </span>
                                <span className="select-more">
                                    <i className="fas fa-ellipsis-v"></i>
                                </span>
                            </span>
                            <Droppable type="pluslet" key={sectionId.toString()} style={{ transform: "none" }} 
                                droppableId={sectionId.toString()} direction="vertical">
                                {(provided, snapshot) => (
                                    <div className="guide-section" data-layout={layout} {...provided.droppableProps} 
                                        ref={provided.innerRef}
                                        style={getSectionDroppableStyle(snapshot.isDraggingOver)}>
                                        {pluslets.length > 0 ? pluslets : 'Add a pluslet'}
                                        {provided.placeholder}
                                    </div>
                                )}
                            </Droppable>
                        </div>
                    )}
                </Draggable>
            );
        }
    }, [data, isLoading, isError]);

    return sectionContent;
}

export default Section;