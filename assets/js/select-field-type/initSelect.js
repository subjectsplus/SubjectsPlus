import React from 'react';
import Select from 'react-select';
import Utility from '../Utility/Utility.js';

const selectElements = document.getElementsByClassName('form-select');

Array.from(selectElements).forEach( element => {
    let options = Array.from(element.options);
    let selectedOptions = Array.from(element.querySelectorAll('option:checked'));
    
    Utility.replaceNodeWithReactComponent(element,
        <Select isMulti={true} id={element.id} name={element.getAttribute('name')} options={options} 
            defaultValue={selectedOptions} />);
});