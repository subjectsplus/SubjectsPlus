function Error({onRetry}) {
    return (
        <div>
            <p>Error has occurred!</p>
            <button onClick={onRetry}>Retry</button>
        </div>
    )
}

export default Error;