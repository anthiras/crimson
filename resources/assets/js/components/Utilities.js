import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {faSpinner} from "@fortawesome/free-solid-svg-icons/index";

const Loading = () => (
    <div className="d-flex justify-content-center text-secondary p-4">
        <FontAwesomeIcon icon={faSpinner} size="4x" className="fa-spin" />
    </div>
);

export { Loading };