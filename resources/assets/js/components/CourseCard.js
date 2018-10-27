import React, {Component} from 'react';
import moment from 'moment';
import CourseSignUp from './CourseSignUp';
import { Link } from 'react-router-dom';

class CourseCard extends Component {
    constructor(props) {
        super(props);
        this.course = props.course;
        this.courseStartsAt = moment(this.course.startsAt);
        this.courseEndsAt = moment(this.course.endsAt);
        this.firstLessonEndsAt = this.courseStartsAt.clone();
        this.firstLessonEndsAt.add(this.course.durationMinutes, 'm');
        this.courseLink = '/courses/'+this.course.id;
        this.state = {
            status: props.course.myParticipation == null ? null : props.course.myParticipation.participation.status
        }
        this.statusChanged = this.statusChanged.bind(this);
    }

    statusChanged(status) {
        this.setState({status: status});
    }

    render() {
        const bgClass = this.state.status == "pending" ? "bg-success text-white" : "bg-light";
        const mutedClass = this.state.status == "pending" ? "" : "text-muted";
        return (
            <div className={"card mb-4 "+bgClass}>
                <div className="card-body">
                    <h5 className="card-title">{ this.course.name }</h5>
                    <h6 className="card-subtitle mb-1">{ this.course.instructors.map(instructor => instructor.name).join(" & ") }</h6>
                    <p className={"card-text "+mutedClass}>{ this.course.weeks } lessons</p>
                    <CourseSignUp status={this.state.status} course={this.course} onStatusChanged={this.statusChanged} />
                    {" "}<Link to={this.courseLink} className="btn btn-secondary">Manage</Link>
                </div>
                <div className="card-footer">
                    <small className={mutedClass}>{ this.courseStartsAt.format("dddd") }s { this.courseStartsAt.format("HH:mm") }–{ this.firstLessonEndsAt.format("HH:mm") } from { this.courseStartsAt.format("MMM D") }</small>
                </div>
            </div>
        );
    }
}

export default CourseCard;