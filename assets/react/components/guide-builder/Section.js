import React, { useState } from 'react';
import Pluslet from './Pluslet';
import { useFetchPluslets, useCreatePluslet } from '#api/guide/PlusletAPI';
import { useDeleteSection } from '#api/guide/SectionAPI';
import { Draggable, Droppable } from 'react-beautiful-dnd';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';
import { v4 as uuidv4 } from 'uuid';

function Section({ tabId, sectionId, isCurrentlyDragging, layout, sectionIndex, currentEditablePluslet, currentEditablePlusletCallBack }) {
    const {isLoading, isError, data, error} = useFetchPluslets(sectionId);

    const deleteSectionMutation = useDeleteSection(tabId);
    const createPlusletMutation = useCreatePluslet(sectionId);

    const [addPlusletHovered, setAddPlusletHovered] = useState(null);

    const getSectionWindowStyle = (isDragging, draggableStyle) => ({
        position: 'relative',
        marginBottom: '2.5rem',
        border: '1px dotted #b5b5b5',
        padding: '0.5rem .75rem',
        background: isDragging ? 'rgba(63,194,198, 15%)' : 'transparent',
        height: isDragging ? '100px' : '',
        ...draggableStyle
      });

    const getSectionContentStyle = (isDraggingOver) => ({
        backgroundColor: isDraggingOver ? 'rgba(0,0,0, 5%)' : 'transparent',
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
                id: uuidv4(),
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
                        <Pluslet key={pluslet.id} sectionId={sectionId}
                            plusletId={pluslet.id} plusletRow={row}
                            plusletTitle={pluslet.title} plusletBody={pluslet.body}
                            currentEditablePluslet={currentEditablePluslet} 
                            currentEditablePlusletCallBack={currentEditablePlusletCallBack} />)
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
                                    {columnPluslets}
                                    {provided.placeholder}
                                    <div className="text-center mt-2">
                                        <button
                                            className="btn btn-muted p-1"
                                            onClick={() => addPluslet(currentColumn, columnRows)}
                                            onMouseEnter={e => {
                                                setAddPlusletHovered(columnId);
                                            }}
                                            onMouseLeave={e => {
                                                setAddPlusletHovered(null);
                                            }}
                                        >
                                            <i className="fas fa-plus-circle d-block"></i>
                                            <span className="fs-xs" style={{
                                                visibility: addPlusletHovered === columnId ? 'visible' : 'hidden'}}>
                                                    Add Pluslet
                                            </span>
                                        </button>
                                    </div>
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
                            style={getSectionWindowStyle(snapshot.isDragging || isCurrentlyDragging, provided.draggableProps.style)}>
                            <div className="drag-handle sp-section-drag-handle" {...provided.dragHandleProps} title="Move section">
                                <i className="fas fa-arrows-alt"></i>
                            </div>
                            <div className="dropdown basic-dropdown">
                                <button className="btn btn-icon-default dropdown-toggle sp-section-menu-btn" id="sectionMenuOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i className="fas fa-ellipsis-h"></i>
                                </button>
                                <ul className="dropdown-menu dropdown-arrow dropdown-menu-end fs-xs" aria-labelledby="sectionMenuOptions">
                                    <li><span className="dropdown-item-text fw-bold fs-sm">Layout</span>
                                        <ul className="sp-section-layout-list">
                                            {/*
                                              sp-col-1 = 12-0-0 or 0-12-0
                                              sp-col-2 = 6-6-0
                                              sp-col-2-left-sidebar = 4-8-0 or 9-3-0
                                              sp-col-2-right-sidebar = 8-4-0
                                              sp-col-3 = 4-4-4
                                              sp-col-3-sidebars = 3-6-3
                                              Don't know what to do with the random ones like 7-5-0
                                            */}
                                            <li><a className="dropdown-item"><span className="sp-col-1"></span></a></li>
                                            <li><a className="dropdown-item"><span className="sp-col-2"></span></a></li>
                                            <li><a className="dropdown-item"><span className="sp-col-2-left-sidebar"></span></a></li>
                                            <li><a className="dropdown-item"><span className="sp-col-2-right-sidebar"></span></a></li>
                                            <li><a className="dropdown-item"><span className="sp-col-3"></span></a></li>
                                            <li><a className="dropdown-item"><span className="sp-col-3-sidebars"></span></a></li>
                                        </ul>
                                    </li>
                                    <li><hr className="dropdown-divider" /></li>
                                    <li><a className="dropdown-item delete-section" onClick={deleteSection}><i
                                        className="fas fa-trash"></i> Delete Section</a></li>
                                </ul>
                            </div>

                            <div className="guide-section-content" data-layout={layout}
                                style={getSectionContentStyle(snapshot.isDragging || isCurrentlyDragging)}>
                                <span className="visually-hidden">Section {sectionId}</span>
                                <Row className={(snapshot.isDragging || isCurrentlyDragging) ? 'visually-hidden' : ''}>
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