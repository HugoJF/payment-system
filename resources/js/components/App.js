import React, {Component, Fragment} from 'react';
import {Route, Switch, withRouter} from "react-router-dom";
import Container from "./ui/Container";
import {setAccent} from "../actions/UI";
import {connect} from "react-redux";
import PendingTradeoffer from "./PendingTradeoffer";
import PaymentMethodSelector from "./PaymentMethodSelector";
import Order from "./order/Order";
import NotFound from "./ui/NotFound";
import Inventory from "./ui/Inventory";

class AppComponent extends Component {
    componentDidUpdate() {
        console.log('App.js updated');
    }

    render() {
        return (
            <Container>
                <Switch>
                    <Route
                        path="/orders/:orderId/:action?"
                        render={(props) => (
                            <Order
                                orderId={props.match.params.orderId}
                                action={props.match.params.action}
                            />
                        )}
                    />
                    <Route
                        render={() => (
                            <NotFound/>
                        )}
                    />
                </Switch>
            </Container>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color) => (dispatch(setAccent(color)))
});

const mapStateToProps = state => ({});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(AppComponent));