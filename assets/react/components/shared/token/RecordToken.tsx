import { htmlEntityDecode } from '@utility/Utility';
import { TitleType, LocationType } from '@shared/types/record_types';

type RecordTokenProps = {
    record: TitleType,
    onClick: React.MouseEventHandler
}

export const RecordToken = ({record, onClick}: RecordTokenProps) => {
    return (
        <div className="record-token" draggable="true" onClick={onClick}
            data-record-id={record.titleId} data-record-title={record.title}
            data-record-description={record.description}
            data-record-location={record.location ? record.location[0].location : null}>
                {htmlEntityDecode(record.title)}
        </div>
    );
};

