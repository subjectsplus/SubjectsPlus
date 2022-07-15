type SearchBarProps = {
    id: string,
    className: string,
    placeholder: string,
    onChange: React.ChangeEventHandler<HTMLInputElement>
}

export const SearchBar = ({id, className, placeholder, onChange}: SearchBarProps) => {
    const ignoreEnterKey = (evt: React.KeyboardEvent) => {
        if (evt.key === 'Enter')
            evt.preventDefault();
    }

    return (
        <form action={void(0)} onSubmit={(evt) => evt.preventDefault()}>
            {/* Label is for accessibility purposes, will not be visible */}
            <div className="mb-2">
                <label htmlFor={id} className="form-label">
                    <span className="visually-hidden">{placeholder}</span>
                </label>
                <input
                    type="text"
                    id={id}
                    placeholder= {placeholder}
                    onChange={onChange}
                    className={className}
                    autoComplete="off"
                    onKeyDown={ignoreEnterKey}
                />
            </div>
        </form>
    );
}