import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class Modal extends Component
{
    componentDidMount() {
        $(ReactDOM.findDOMNode(this)).modal('show');
        $(ReactDOM.findDOMNode(this)).on('hidden.bs.modal', this.props.onClose);
    }

    render() {
        return (
            <div className="modal fade" tabIndex="-1" role="dialog" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered" role="document">
                    <div className="modal-content">
                        {this.props.children}
                    </div>
                </div>
            </div>
        );
    }
}

Modal.Header = (props) => (
    <div className="modal-header">
        <h5 className="modal-title">{props.children}</h5>
        <button type="button" className="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
);

Modal.Body = (props) => (
    <div className="modal-body">
        {props.children}
    </div>
);

Modal.Footer = (props) => (
    <div className="modal-footer">
        {props.children}
    </div>
);

Modal.Close = (props) => (
    <button type="button" className="btn btn-secondary" data-dismiss="modal">
        {props.children ? props.children : "Close"}
    </button>
);

export default Modal;