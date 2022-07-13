import { useState } from 'react';
import { Pluslet } from '../Pluslet';
import Col from 'react-bootstrap/Col';
import { Droppable } from 'react-beautiful-dnd';
import { PlusletType } from '@shared/types/guide_types';
import { useSectionContainer, SectionContainerType } from '@context/SectionContainerContext';

type SectionColumnProps = {
    columnId: string,
    sectionUUID: string,
    pluslets: PlusletType[]|null,
    columnSize: number,
    addPlusletOnClick: React.MouseEventHandler<HTMLButtonElement>
}

export const SectionColumn = ({ columnId, sectionUUID, pluslets, columnSize, addPlusletOnClick }: SectionColumnProps) => {
    const [addPlusletHovered, setAddPlusletHovered] = useState<boolean>(false);
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
                        <div className="text-center mt-2">
                            <button
                                className="btn btn-muted p-1"
                                onClick={addPlusletOnClick}
                                onMouseEnter={e => {
                                    setAddPlusletHovered(true);
                                }}
                                onMouseLeave={e => {
                                    setAddPlusletHovered(false);
                                }}
                            >
                                <i className="fas fa-plus-circle d-block"></i>
                                <span className={'fs-xs' + (addPlusletHovered ? '' : ' invisible')}>
                                        Add Box
                                </span>
                            </button>
                        </div>
                    </div>
                )}
            </Droppable>
        </Col>
    );
}