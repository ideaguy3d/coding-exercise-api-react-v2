import React, {Component} from 'react';
import {Link} from 'react-router-dom';

class Nav extends Component {
    navStyle = {
        color: 'white'
    }

    render() {
        return (
            <nav className="breeze">
                <h3><span role="img" aria-label="logo">⛵️</span>Breeze Church Management</h3>
                <ul className="nav-links">
                    <Link style={this.navStyle} to="/">
                        <li>People</li>
                    </Link>
                    <Link style={this.navStyle} to="/groups">
                        <li>Groups</li>
                    </Link>
                    <Link style={this.navStyle} to="/about">
                        <li>About</li>
                    </Link>
                </ul>
            </nav>
        );
    }
}

export default Nav;