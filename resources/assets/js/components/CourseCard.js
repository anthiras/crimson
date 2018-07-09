import React, { Component } from 'react';
import moment from 'moment';
import { post } from './Api';

class SignUpButton extends Component {
    constructor(props) {
        super(props);
        this.courseId = props.courseId;
        this.state = {
            signedUp: props.participation != null &&
                (props.participation.status == "pending" || props.participation.status == "confirmed")
        }
        this.signUp = this.signUp.bind(this);
        this.cancel = this.cancel.bind(this);
    }

    signUp() {
        post('api/courses/'+this.courseId+'/signUp')
            .then(() => { this.setState({signedUp: true}); });
    }

    cancel() {
        post('api/courses/'+this.courseId+'/cancelSignUp')
            .then(() => { this.setState({signedUp: false}); });
    }

    render() {
        return this.state.signedUp
            ? (<button className="btn btn-success" onClick={this.cancel}>Signed up!</button>)
            : (<button className="btn btn-primary" onClick={this.signUp}>Sign up</button>)
    }
}

class CourseCard extends Component {
    constructor(props) {
        super(props);
        this.course = props.course;
        this.courseStartsAt = moment(this.course.startsAt);
        this.courseEndsAt = moment(this.course.endsAt);
        this.firstLessonEndsAt = this.courseStartsAt.clone();
        this.firstLessonEndsAt.add(this.course.durationMinutes, 'm');
    }

    render() {
        return (
            <div className="card bg-light mb-4">
                <div className="card-body">
                    <h5 className="card-title">{ this.course.name }</h5>
                    <h6 className="card-subtitle mb-1">{ this.course.instructors.map(instructor => instructor.name).join(" & ") }</h6>
                    <p className="card-text text-muted">{ this.course.weeks } lessons</p>
                    <SignUpButton participation={this.course.myParticipation} courseId={this.course.id} />
                </div>
                <div className="card-footer">
                    <small className="text-muted">{ this.courseStartsAt.format("dddd") }s { this.courseStartsAt.format("HH:mm") }–{ this.firstLessonEndsAt.format("HH:mm") } from { this.courseStartsAt.format("MMM D") }</small>
                </div>
            </div>
        );
    }
}

export default CourseCard;