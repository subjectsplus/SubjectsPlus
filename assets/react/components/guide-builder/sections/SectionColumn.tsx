import { useState } from 'react';
import { Pluslet } from '../pluslets/Pluslet';
import Col from 'react-bootstrap/Col';
import { Droppable } from 'react-beautiful-dnd';
import { PlusletType } from '@shared/types/guide_types';
import { useSectionContainer, SectionContainerType } from '@context/SectionContainerContext';
import { AddPlusletButton } from '../pluslets/shared/AddPlusletButton';

type SectionColumnProps = {
    columnId: string,
    sectionUUID: string,
    pluslets: PlusletType[]|null,
    columnSize: number,
    addPlusletCallback: (plusletType: string) => void
}

export const SectionColumn = ({ columnId, sectionUUID, pluslets, columnSize, addPlusletCallback }: SectionColumnProps) => {
    const { currentDraggingId } = useSectionContainer() as SectionContainerType;

    const plusletIsCurrentlyDragging = (currentDraggingId && currentDraggingId.substring(0, 8) === 'pluslet-');
    
    const getGuideColumnClassName = () => {
        let className = 'sp-guide-column';

        if (plusletIsCurrentlyDragging) {
            className += ' sp-guide-column-dragging-over';
        }

        return className;
    }

    const getPlusletComponents = () => {
        if (pluslets && pluslets.length > 0) {
            return pluslets.map((pluslet, row) => (
                <Pluslet key={pluslet.id} pluslet={pluslet}
                    plusletRow={row} sectionUUID={sectionUUID} />
            ));
        }
    }

    return (
        <Col lg={Number(columnSize)} className="mb-3 mb-lg-0">
            <Droppable type="pluslet" droppableId={columnId} direction="vertical">
                {(provided, snapshot) => (
                    <div className={getGuideColumnClassName()} {...provided.droppableProps} ref={provided.innerRef}>
                        <span className="visually-hidden">{columnId}</span>
                        {getPlusletComponents()}
                        {provided.placeholder}
                        
                        {/* Add Pluslet Button */}
                        <AddPlusletButton addPlusletCallback={addPlusletCallback} />
                    </div>
                )}
            </Droppable>
        </Col>
    );
}