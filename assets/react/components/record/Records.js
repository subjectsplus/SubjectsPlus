import React from "react";
import { useQuery} from "react-query";
// import * as api from '../../api/Record/RecordApi';
// import {useFetchRecords} from "../../api/Record/RecordApi";



function Records() {

    const apiLink = '/api/titles';

    const getApiLink = (letter, page=1) => {
        return apiLink + '?' + new URLSearchParams({
            letter: letter,
            'location.eresDisplay': 'Y',
            page: page
        });
    }
    const fetchRecords = async () => {
        const response = await fetch(getApiLink('A'));
        return response.json();
    }

    const {isLoading, isError, data, error} = useQuery(["records", "A"], () => fetchRecords(), {
            select: data => data['hydra:member']
            }
        );


    const recordsContent = () => {
        if (isLoading) {
            return (<p>Loading Records...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            return (

                    <div>
                        {data.map((record) => record.title)}
                    </div>

            );
        }
    }

    return recordsContent();
}

export default Records;