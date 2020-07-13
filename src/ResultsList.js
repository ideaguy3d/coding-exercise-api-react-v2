import React, {Component} from 'react'
import _ from 'lodash';
import {Table} from 'semantic-ui-react'

class ResultsList extends Component {
    state = {
        column: null,
        data: null,
        direction: null
    }

    constructor(props) {
        super(props);
        this.state = {data: []};
    }

    componentDidMount() {
        fetch("http://localhost:8000/api/people")
            .then(response => response.json())
            .then(data => this.setState({data: data.data}));
    }

    handleSort = (clickedColumn) => () => {
        const {column, data, direction} = this.state;

        if (column !== clickedColumn) {
            this.setState({
                column: clickedColumn,
                data: _.sortBy(data, [clickedColumn]),
                direction: 'ascending',
            })

            return;
        }

        this.setState({
            data: data.reverse(),
            direction: direction === 'ascending' ? 'descending' : 'ascending',
        })
    }

    render() {
        let data = this.state.data || [];
        let column = this.state.column;
        let direction = this.state.direction;
        let first = 'first_name', last = 'last_name', email = 'email_address', status = 'status';

        return (
            <Table celled padded sortable>
                <Table.Header>
                    <Table.Row>
                        {/* First Name column */}
                        <Table.HeaderCell
                            sorted={column === first ? direction : null}
                            onClick={this.handleSort(first)}>
                            First Name</Table.HeaderCell>

                        {/* Last Name column */}
                        <Table.HeaderCell
                            sorted={column === last ? direction : null}
                            onClick={this.handleSort(last)}>
                            Last Name</Table.HeaderCell>

                        {/* Email column */}
                        <Table.HeaderCell
                            sorted={column === email ? direction : null}
                            onClick={this.handleSort(email)}>
                            Email</Table.HeaderCell>

                        {/* Status column */}
                        <Table.HeaderCell
                            sorted={column === status ? direction : null}
                            onClick={this.handleSort(status)}>
                            Status</Table.HeaderCell>
                    </Table.Row>
                </Table.Header>

                <Table.Body>
                    {
                        data.map((person, index) => {
                            return (
                                <Table.Row key={index}>
                                    <Table.Cell singleLine>{person.first_name}</Table.Cell>
                                    <Table.Cell singleLine>{person.last_name}</Table.Cell>
                                    <Table.Cell singleLine>{person.email_address}</Table.Cell>
                                    <Table.Cell singleLine>{person.status}</Table.Cell>
                                </Table.Row>
                            );
                        })
                    }

                </Table.Body>
            </Table>
        );
    }

}

export default ResultsList
