import { htmlEntityDecode } from '@utility/Utility';
import { RecordToken } from './RecordToken';
import { MediaToken } from './MediaToken';
import { TitleType } from '@shared/types/record_types';
import { MediaType } from '@shared/types/media_types';

type TokenProps = {
    token: Record<string, any>,
    tokenType: string,
    onClick: React.MouseEventHandler
}

type GenericTokenType = {
    title: string,
}

export const Token = ({ token, tokenType, onClick }: TokenProps) => {
    if (tokenType === 'record') {
        return (<RecordToken record={(token as TitleType)} onClick={onClick} />)
    } else if (tokenType === 'media') {
        return (<MediaToken media={(token as MediaType)} onClick={onClick} />)
    } else {
        // generic token
        const dataAttributes:Record<string, any> = {};

        for (const tokenKey in token) {
            const cleanedTokenKey = tokenKey.split(/(?=[A-Z])/).join('-').toLowerCase();
            const attributeName = 'data-' + cleanedTokenKey;
            
            dataAttributes[attributeName] = String(token[tokenKey]);
        }

        return (<div className="token" draggable="true" onClick={onClick} {...dataAttributes}>
            {htmlEntityDecode((token as GenericTokenType).title)}
    </div> )
    }
}