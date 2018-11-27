import React, {Component} from 'react';
import CourseSignUp from './CourseSignUp';
import { Link } from 'react-router-dom';
import { withNamespaces } from 'react-i18next';

class CourseCard extends Component {
    constructor(props) {
        super(props);
        this.course = props.course;
        this.courseStartsAt = new Date(this.course.startsAt);
        this.courseEndsAt = new Date(this.course.endsAt);
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
        const t = this.props.t;
        const bgClass = this.state.status === "pending" ? "bg-success text-white" : "bg-light";
        const mutedClass = this.state.status === "pending" ? "" : "text-muted";
        return (
            <div className={"card mb-4 "+bgClass}>
                <div className="card-body">
                    <h5 className="card-title">{ this.course.name }</h5>
                    <h6 className="card-subtitle mb-1">{ this.course.instructors.map(instructor => instructor.name).join(" & ") }</h6>
                    <p className={"card-text "+mutedClass}>{ t('courses:xLessons', {count: this.course.weeks}) }</p>
                    <CourseSignUp status={this.state.status} course={this.course} onStatusChanged={this.statusChanged} />
                    {" "}{this.course.canShow && (<Link to={this.courseLink} className="btn btn-secondary">{t('common:manage')}</Link>)}
                </div>
                <div className="card-footer">
                    <small className={mutedClass}>{ t('courses:scheduleSummary', { startDate: this.courseStartsAt, endDate: this.courseEndsAt, count: this.course.weeks }) }</small>
                </div>
            </div>
        );
    }
}

export default withNamespaces()(CourseCard);