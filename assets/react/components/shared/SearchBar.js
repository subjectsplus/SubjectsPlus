function SearchBar(props) {
    const ignoreEnterKey = evt => {
        if (evt.keyCode === 13)
            evt.preventDefault();
    }

    return (
        <form action={void(0)} onSubmit={(evt) => evt.preventDefault()}>
            {/* Label is for accessibility purposes, will not be visible */}
            <div className="mb-2">
                <label htmlFor={props.id} className="form-label">
                    <span className="visually-hidden">{props.placeholder}</span>
                </label>
                <input
                    type="text"
                    id={props.id}
                    placeholder= {props.placeholder}
                    onChange={props.onChange}
                    className={props.className}
                    autoComplete="off"
                    onKeyDown={ignoreEnterKey}
                />
            </div>
        </form>
    );
}

export default SearchBar;