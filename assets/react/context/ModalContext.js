import { createContext, useContext, useState } from 'react';

export const ModalContext = createContext(null);

export function ModalProvider({ children }) {
  const [isHidden, setIsHidden] = useState(false);

  const value = {
    isHidden,
    setIsHidden,
    toggle: () => {
      setIsHidden(current => !current)
    }
  };
  
  return <ModalContext.Provider value={value}>{children}</ModalContext.Provider>
}

export function useModal() {
  return useContext(ModalContext)
}