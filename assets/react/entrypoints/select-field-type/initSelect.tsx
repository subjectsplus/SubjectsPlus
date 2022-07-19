import Select, { StylesConfig } from 'react-select';
import { replaceNodeWithReactComponent } from '@utility/Utility';

const multiSelectElements = document.getElementsByClassName('form-select-multi') as HTMLCollectionOf<HTMLSelectElement>;

const singleSelectElements = document.getElementsByClassName('form-select') as HTMLCollectionOf<HTMLSelectElement>;

const reactSelectCustomStyles: StylesConfig<Element, true> = {
    control: (provided, state) => ({
        ...provided,
        backgroundColor: 'var(--sp-input-bg)',
        borderColor: state.isFocused ? 'var(--sp-input-focus-border-color)' : 'var(--sp-input-border-color)',
        boxShadow: state.isFocused ? '0 0 0 0.25rem rgba(var(--sp-focus-box-shadow-color), 20%)' : 'none',
        '&:hover': {
            borderColor: 'none'
        }
    }),
    placeholder: (provided) => ({
        ...provided,
        color: 'var(--sp-input-placeholder-color)'
    }),
    input: (provided) => ({
        ...provided,
        color: 'var(--sp-input-color)'
    }),
    indicatorsContainer: (provided, state: any) => ({
        ...provided,
        color: state.isFocused ? 'var(--sp-input-focus-border-color)' : 'var(--sp-input-border-color)',
        '&:hover': {
            color: 'var(--sp-input-focus-border-color)'
        }
    }),
    menu: (provided) =>({
        ...provided,
        color: 'var(--sp-input-color)'
    }),
    multiValue: (provided) =>({
        ...provided,
        color: 'var(--sp-input-color)',
        backgroundColor: 'rgba(var(--sp-accent-color-rgb), 55%)'
    }),
    multiValueLabel: (provided) =>({
        ...provided,
        color: 'var(--sp-input-color)'
    }),
    option: (provided) =>({
        ...provided,
        color: 'var(--sp-input-color)',
        backgroundColor: 'transparent',
        '&:hover': {
            color: 'var(--sp-input-selected-text)',
            backgroundColor: 'var(--sp-input-selected-bg)'
        }
    }),
    noOptionsMessage: (provided) =>({
        ...provided,
        color: 'var(--sp-input-color)'
    })
}

Array.from(multiSelectElements).forEach((element: HTMLSelectElement) => {
    let options = Array.from(element.options);
    let selectedOptions = Array.from(element.querySelectorAll('option:checked'));
    
    replaceNodeWithReactComponent(element,
        <Select isMulti={true} id={element.id} classNamePrefix="sp-react-select-multi" 
            styles={reactSelectCustomStyles} name={element.getAttribute('name') || ''} 
            placeholder={element.getAttribute('data-placeholder-text')}
            noOptionsMessage={() => element.getAttribute('data-no-options-message')}
            options={options} defaultValue={selectedOptions} />);
});

Array.from(singleSelectElements).forEach( element => {
    let options = Array.from(element.options);
    let selectedOptions = Array.from(element.querySelectorAll('option:checked'));

    replaceNodeWithReactComponent(element,
        <Select id={element.id} classNamePrefix="sp-react-select" styles={reactSelectCustomStyles} 
            name={element.getAttribute('name') || ''} placeholder={element.getAttribute('data-placeholder-text')} 
            noOptionsMessage={() => element.getAttribute('data-no-options-message')} options={options} 
            isSearchable={false}
            defaultValue={selectedOptions} />);
});