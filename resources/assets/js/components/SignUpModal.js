import React, {Component} from "react";
import {post} from "./Api";
import Modal from 'react-bootstrap4-modal';
import { withNamespaces } from 'react-i18next';

class SignUpModal extends Component
{
    constructor(props) {
        super(props);
        this.modalId = "signup" + props.course.id;
        this.signupUrl = 'api/courses/' + props.course.id + '/signUp';
        this.submitSignup = this.submitSignup.bind(this);
        this.setSignUpDetails = this.setSignUpDetails.bind(this);
        this.state = {
            signUpDetails: {
                role: null
            },
            error: false
        }
    }

    setSignUpDetails(key, e) {
        var state = Object.assign({}, this.state.signUpDetails);
        state[key] = e.target.value;
        this.setState({signUpDetails: state});
    }

    submitSignup(e) {
        e.preventDefault();
        this.setState({error: false});
        post(this.signupUrl, this.state.signUpDetails)
            .then(this.props.onSignedUp)
            .catch(() => this.setState({error: true}));
    }

    render() {
        const t = this.props.t;
        return <Modal visible={this.props.visible} onClickBackdrop={this.props.onClose}>
            <form onSubmit={this.submitSignup}>
                <div className="modal-header">
                    <h5 className="modal-title">{t('courses:signupFor')} {this.props.course.name}</h5>
                    <button type="button" className="close" aria-label="Close" onClick={this.props.onClose}>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div className="modal-body">
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="role" id={this.modalId + "_lead"}
                               value="lead" onChange={(e) => this.setSignUpDetails('role', e)} required />
                        <label className="form-check-label" htmlFor={this.modalId + "_lead"}>{t('courses:lead')}</label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="role"
                               id={this.modalId + "_follow"}
                               value="follow" onChange={(e) => this.setSignUpDetails('role', e)} required />
                        <label className="form-check-label" htmlFor={this.modalId + "_follow"}>{t('courses:follow')}</label>
                    </div>
                    {this.state.error && <div className="alert alert-danger">{t('courses:signupError')}</div>}
                </div>
                <div className="modal-footer">
                    <button type="button" onClick={this.props.onClose} className="btn btn-secondary">{t('common:cancel')}
                    </button>
                    <button type="submit" className="btn btn-primary">{t('actions:confirmSignup')}</button>
                </div>
            </form>
        </Modal>
    }
}

export default withNamespaces()(SignUpModal);