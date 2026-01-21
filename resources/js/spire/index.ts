/**
 * Spire UI - Modular Entry Point
 * 
 * This is the main entry point for the modular version of Spire UI.
 * It re-exports all components and utilities in a tree-shakeable way.
 */

// Types
export * from './types';

// Core
export { instances, emit, globalErrorHandler, setGlobalErrorHandler } from './core';

// Components
export * from './components';

// Utilities
export * from './utilities';

// Re-export individual components for direct imports
export { Accordion } from './components/Accordion';
export { Button } from './components/Button';
export { Carousel } from './components/Carousel';
export { Clipboard } from './components/Clipboard';
export { Collapse } from './components/Collapse';
export { ColorPicker } from './components/ColorPicker';
export { CommandPalette } from './components/CommandPalette';
export { ContextMenu } from './components/ContextMenu';
export { DatePicker } from './components/DatePicker';
export { DateRangePicker } from './components/DateRangePicker';
export { Drawer } from './components/Drawer';
export { Dropdown } from './components/Dropdown';
export { FileUpload } from './components/FileUpload';
export { FormValidator } from './components/FormValidator';
export { InfiniteScroll } from './components/InfiniteScroll';
export { Input } from './components/Input';
export { LazyLoad } from './components/LazyLoad';
export { Modal } from './components/Modal';
export { MultiSelect } from './components/MultiSelect';
export { Persist } from './components/Persist';
export { Popover } from './components/Popover';
export { Progress } from './components/Progress';
export { RangeSlider } from './components/RangeSlider';
export { Rating } from './components/Rating';
export { Select } from './components/Select';
export { Skeleton } from './components/Skeleton';
export { Stepper } from './components/Stepper';
export { Table } from './components/Table';
export { Tabs } from './components/Tabs';
export { Tooltip } from './components/Tooltip';
export { VirtualScroll } from './components/VirtualScroll';

// Re-export utilities for direct imports
export { toast } from './utilities/Toast';
export { confirm } from './utilities/Confirm';
export { shortcuts } from './utilities/Shortcuts';
export { events } from './utilities/EventBus';
export { http } from './utilities/Http';
export { currency } from './utilities/Currency';
export { mask } from './utilities/Mask';
export { perf } from './utilities/Perf';
export { a11y } from './utilities/A11y';
export { debounce, throttle } from './utilities/timing';
