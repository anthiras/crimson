import React, {Component} from 'react';
import ParticipantRow from "./ParticipantRow";

class ParticipantList extends Component
{
    render() {
        return <table className="table">
            <thead>
                <tr>
                    <th style={{width: "5%"}}>#</th>
                    <th style={{width: "5%"}}></th>
                    <th style={{width: "30%"}}>Name</th>
                    <th style={{width: "10%"}}>Role</th>
                    <th style={{width: "15%"}}>Signup date</th>
                    <th style={{width: "15%"}}>Status</th>
                    <th style={{width: "20%"}}>Actions</th>
                </tr>
            </thead>
            <tbody>
                {this.props.participants.map((participant, i) => <ParticipantRow key={participant.id} participant={participant} number={i+1} />)}
            </tbody>
        </table>
    }
}

export default ParticipantList;