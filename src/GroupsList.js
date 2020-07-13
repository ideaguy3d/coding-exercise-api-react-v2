import React, {Component} from 'react';
import {List, Image} from 'semantic-ui-react';

class GroupsList extends Component {
    constructor(props) {
        super(props);
        this.state = {data: []};
    }

    componentDidMount() {
        fetch("http://localhost:8000/api/groups")
            .then(res => res.json())
            .then(data => this.setState({data: data.data}));
    }

    render() {
        let data = this.state.data || [];

        return (
            <List celled>
                {
                    data.map((group, index) => {
                        return (
                            <List.Item key={index}>
                                <List.Icon name='group'/>
                                <List.Content>
                                    <List.Header as="h3">{group.group_name}</List.Header>
                                    {
                                        group.members.map((name, index2) => {
                                            return (
                                                <List.Item key={index2}>
                                                    <List.Content>
                                                        <List.Header>
                                                            <List.Icon name='user circle' />
                                                            {name}
                                                        </List.Header>
                                                    </List.Content>
                                                </List.Item>
                                            );
                                        })
                                    }

                                </List.Content>
                            </List.Item>
                        );
                    })
                }
            </List>
        );
    }

}

export default GroupsList;