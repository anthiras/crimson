import React, {Component} from "react";
import {post} from "./Api";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {faCheckCircle} from "@fortawesome/free-solid-svg-icons/index";
import SignUpModal from './SignUpModal';
import Auth from './Auth'

class CourseSignUp extends Component {
    constructor(props) {
        super(props);
        this.course = props.course;
        this.state = {
            modalOpen: false
        }
        this.auth = new Auth();
        this.cancel = this.cancel.bind(this);
        this.openSignUpModal = this.openSignUpModal.bind(this);
        this.closeSignUpModal = this.closeSignUpModal.bind(this);
        this.onSignedUp = this.onSignedUp.bind(this);
    }

    cancel(e) {
        e.preventDefault();
        post('api/courses/' + this.course.id + '/cancelSignUp')
            .then(({status}) => this.props.onStatusChanged(status));
    }

    openSignUpModal() {
        if (!this.auth.isAuthenticated()) {
            this.auth.login();
            return;
        }
        this.setState({modalOpen: true});
    }

    closeSignUpModal() {
        this.setState({modalOpen: false});
    }

    onSignedUp(response) {
        this.closeSignUpModal();
        this.props.onStatusChanged(response.status);
    }

    render() {
        const status = this.props.status;

        return (
            <React.Fragment>
                <SignUpModal visible={this.state.modalOpen} course={this.course} onClose={this.closeSignUpModal}
                             onSignedUp={this.onSignedUp}  />
                {status === "pending" &&
                    <p>
                        <FontAwesomeIcon icon={faCheckCircle} size="lg"/>
                        <span> Signup requested </span>
                        <a href="#" className="text-white" onClick={this.cancel}>(cancel)</a>
                    </p>
                }
                {status === "confirmed" &&
                    <p><FontAwesomeIcon icon={faCheckCircle} size="lg"/> Signup confirmed</p>
                }
                {(status === null || status === "cancelled") &&
                    <button className="btn btn-primary" onClick={this.openSignUpModal}>Sign up...</button>
                }
            </React.Fragment>
        );
    }
}

export default CourseSignUp;