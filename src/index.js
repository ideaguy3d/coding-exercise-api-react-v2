import React from "react";
import ReactDOM from "react-dom";
import {Container, Header} from "semantic-ui-react";
import {BrowserRouter as Router, Switch, Route} from 'react-router-dom';

import ResultsList from "./ResultsList";
import BreezeUpload from "./BreezeUpload";
import People from "./People";
import Group from "./Group";
import Nav from './Nav';
import About from './About';
import './index.css';

const App = ({children}) => (
    <Container style={{margin: 20}}>
        <Router>
            <Nav/>
            <Switch>
                <Route path="/" exact component={People}/>
                <Route path="/about" component={About}/>
                <Route path="/groups" component={Group}/>
            </Switch>
        </Router>

        {children}
    </Container>
);

const styleLink = document.createElement("link");
styleLink.rel = "stylesheet";
styleLink.href = "https://cdn.jsdelivr.net/npm/semantic-ui/dist/semantic.min.css";
document.head.appendChild(styleLink);

ReactDOM.render(
    <App></App>,
    document.getElementById("root")
);
