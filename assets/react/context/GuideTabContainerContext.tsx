import { createContext, useContext, useState } from 'react';
import { GuideTabType } from '@shared/types/guide_types';

type GuideTabContainerProviderProps = {
  subjectId: number,
  currentTab: GuideTabType,
  activeKey: number,
  setActiveKey: React.Dispatch<React.SetStateAction<number>>,
  children: React.ReactNode
}

export type GuideTabContainerType = {
  subjectId: number,
  currentTab: GuideTabType
  activeKey: number,
  setActiveKey: React.Dispatch<React.SetStateAction<number>>
}

export const GuideTabContainerContext = createContext<GuideTabContainerType | null>(null);

export const GuideTabContainerProvider = ({ subjectId, currentTab, activeKey, setActiveKey, children }: GuideTabContainerProviderProps) => {
  const value = {
    subjectId,
    currentTab,
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