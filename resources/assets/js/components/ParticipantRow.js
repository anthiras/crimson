import React, {Component} from 'react';

class ParticipantRow extends Component
{
    constructor(props) {
        super(props);
    }
    render() {
        const {
            picture,
            name,
            participation
        } = this.props.participant;

        return <tr>
            <td>{this.props.number}</td>
            <td><img src={picture} width="50" height="50" /></td>
            <td>{name}</td>
            <td>{participation.role}</td>
            <td>{participation.createdAt}</td>
            <td>
                {participation.status}
            </td>
            <td>
                {participation.status === 'pending' && (
                    <React.Fragment>
                        <button type="button" className="btn btn-success">Confirm</button>{" "}
                        <button type="button" className="btn btn-danger">Cancel</button>
                    </React.Fragment>
                )}
                {participation.status === 'confirmed' && (
                    <button type="button" className="btn btn-danger">Cancel</button>
                )}
            </td>
        </tr>
    }
}

export default ParticipantRow;