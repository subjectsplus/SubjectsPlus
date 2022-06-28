import { createContext, useContext, useState } from 'react';

export const FormContext = createContext(null);

export function FormProvider({ children }) {
    const [isValidated, setIsValidated] = useState(false);

    const value = {
        isValidated,
        setIsValidated,
    };

    return (
        <FormContext.Provider value={value}>
            {children}
        </FormContext.Provider>
    );
}

export function useForm() {
    return useContext(FormContext);
}