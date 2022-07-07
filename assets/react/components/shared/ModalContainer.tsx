import Modal from 'react-bootstrap/Modal';

type ModalContainerProps = {
    title: JSX.Element|string,
    children: React.ReactNode,
    footer: JSX.Element|null,
    show: boolean,
    onHide: () => void
}

export const ModalContainer = ({title, children, footer, show, onHide}: ModalContainerProps) => {
    return (
        <Modal show={show} onHide={onHide}>
            <Modal.Header closeButton>
                <Modal.Title>
                    {title}
                </Modal.Title>
            </Modal.Header>
            <Modal.Body>
                {children}
            </Modal.Body>
            {footer && <Modal.Footer>
                {footer}
            </Modal.Footer>}
        </Modal>
    );
}