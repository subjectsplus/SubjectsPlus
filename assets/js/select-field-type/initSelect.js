import React from 'react';
import Select from 'react-select';
import Utility from '../Utility/Utility.js';

const selectElements = document.getElementsByClassName('form-select');

const reactSelectCustomStyles = {
    control: (provided, state) => ({
        ...provided,
        borderColor: 'var(--sp-react-select-border)',
    }),
    placeholder: (provided, state) => ({
        ...provided,
        color: 'var(--sp-react-select-border)',
    }),
}

Array.from(selectElements).forEach( element => {
    let options = Array.from(element.options);
    let selectedOptions = Array.from(element.querySelectorAll('option:checked'));
    
    Utility.replaceNodeWithReactComponent(element,
        <Select isMulti={true} id={element.id} classNamePrefix="sp-react-select" styles={reactSelectCustomStyles} name={element.getAttribute('name')} placeholder={element.getAttribute('data-placeholder-text')} options={options}
            defaultValue={selectedOptions} />);
});