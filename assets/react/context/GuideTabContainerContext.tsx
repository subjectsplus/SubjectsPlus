import { createContext, useContext, useState } from 'react';
import { GuideTabType } from '@shared/types/guide_types';

type GuideTabContainerProviderProps = {
  subjectId: number,
  children: React.ReactNode
}

export type GuideTabContainerType = {
  subjectId: number,
  currentTab: GuideTabType|null,
  setCurrentTab: React.Dispatch<React.SetStateAction<GuideTabType|null>>
  activeKey: number,
  setActiveKey: React.Dispatch<React.SetStateAction<number>>
}

export const GuideTabContainerContext = createContext<GuideTabContainerType | null>(null);

export const GuideTabContainerProvider = ({ subjectId, children }: GuideTabContainerProviderProps) => {
  const [activeKey, setActiveKey] = useState<number>(0);
  const [currentTab, setCurrentTab] = useState<GuideTabType|null>(null);

  const value = {
    subjectId,
    currentTab,
    setCurrentTab,
    activeKey,
    setActiveKey
  };

  return (
    <GuideTabContainerContext.Provider value={value}>
      {children}
    </GuideTabContainerContext.Provider>
  );
}

export const useGuideTabContainer = () => {
  return useContext(GuideTabContainerContext);
}