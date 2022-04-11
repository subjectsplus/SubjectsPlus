import React from 'react';
import Pluslet from './Pluslet';
import { useFetchPluslets, useCreatePluslet } from '#api/guide/PlusletAPI';
import { Draggable, Droppable } from 'react-beautiful-dnd';
import { useDeleteSection } from '#api/guide/SectionAPI';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';
import { v4 as uuidv4, validate as uuidValidate } from 'uuid';

function Section({ tabId, sectionId, layout, sectionIndex }) {
    const {isLoading, isError, data, error} = useFetchPluslets(sectionId);

    const deleteSectionMutation = useDeleteSection(tabId);
    const createPlusletMutation = useCreatePluslet(sectionId);

    const getSectionDraggableStyle = (isDragging, draggableStyle) => ({
        position: 'relative',
        marginBottom: '2.5rem',
        border: '1px dotted #b5b5b5',
        padding: '0.5rem .75rem',
        background: isDragging ? 'rgba(63,194,198, 15%)' : 'transparent',
        ...draggableStyle
      });

    const getSectionStyle = (isDraggingOver) => ({
        backgroundColor: isDraggingOver ? "rgba(0,0,0, 5%)" : "transparent"
    });

    const deleteSection = () => {
        const confirmed = confirm('Are you sure you want to delete this section?');
        if (confirmed) {
            deleteSectionMutation.mutate({
                sectionId: sectionId,
            });
        }
    }

    const addPluslet = (column, row) => {
        if (Array.isArray(data)) {
            const initialPlusletData = {
                uuid: uuidv4(),
                title: "Untitled",
                type: "Basic",
                body: "",
                pcolumn: column,
                prow: row,
                section: '/api/sections/' + sectionId
            }

            console.log('initialPlusletData: ', initialPlusletData);
            createPlusletMutation.mutate(initialPlusletData);
        }
    }

    const generateColumns = () => {
        const splitLayout = layout.split('-');
        let column = 0;
        let pluslets = Array.isArray(data) ? [...data] : null;

        const columns = splitLayout.map(size => {
            if (Number(size) !== 0) {
                let columnPluslets;

                // set current column and increment by 1
                const currentColumn = column++;

                if (pluslets && pluslets.length > 0) {
                    columnPluslets = pluslets.filter(pluslet => pluslet.pcolumn === currentColumn)
                    .filter(pluslet => pluslet !== undefined)
                    .map((pluslet, row) => (
                        <Pluslet key={pluslet.uuid} 
                            plusletId={pluslet.uuid} plusletRow={row} />)
                    );
                }

                const columnId = `section|${sectionId.toString()}|column|${currentColumn}`;
                const columnRows = Array.isArray(columnPluslets) ? columnPluslets.length : 0;

                return (     
                    <Col key={columnId} lg={Number(size)} className="mb-3 mb-lg-0">
                        <Droppable type="pluslet" style={{ transform: 'none' }}
                            droppableId={columnId} direction="vertical">
                            {(provided, snapshot) => (
                                <div className="sp-guide-column" {...provided.droppableProps} ref={provided.innerRef}>
                                    <span className="visually-hidden">{columnId}</span>
                                    <div className="text-center mb-2">
                                        <button className="btn btn-link p-1" title="Add Pluslet">
                                            <i className="fas fa-plus-circle" onClick={() => addPluslet(currentColumn, columnRows)}></i>
                                        </button>
                                    </div>
                                    {columnPluslets}
                                    {provided.placeholder}
                                </div>
                            )}
                        </Droppable>
                    </Col>
                );
            }
        });

        return columns;
    };

    const sectionContent = () => {
        if (isLoading) {
            return (<p>Loading Pluslets...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            return (
                <Draggable type="section" draggableId={'section-' + sectionId} index={sectionIndex}>
                    {(provided, snapshot) => (
                        <div ref={provided.innerRef} {...provided.draggableProps}
                            style={getSectionDraggableStyle(snapshot.isDragging, provided.draggableProps.style)}>
                            <div className="drag-handle sp-section-drag-handle" {...provided.dragHandleProps} title="Move section">
                                <i className="fas fa-arrows-alt"></i>
                            </div>
                            <button className="delete-section btn btn-icon-default sp-section-delete-btn" onClick={deleteSection} title="Delete section">
                                <i className="fas fa-trash"></i>
                            </button>

                            <div className="guide-section" data-layout={layout}
                                style={getSectionStyle(snapshot.isDragging)}>
                                <span className="visually-hidden">Section {sectionId}</span>
                                <Row>
                                    {generateColumns()}
                                </Row>
                            </div>
                        </div>
                    )}
                </Draggable>
            );
        }
    };

    return sectionContent();
}

export default Section;