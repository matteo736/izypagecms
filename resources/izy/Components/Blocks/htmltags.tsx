import {
  Text,
  Heading,
  Image,
  Video,
  List,
  ListOrdered,
  Layout,
  Code,
  Quote,
  Table,
  SquareMousePointer,
  ArrowDown,
  PanelTop,
  BadgeInfo,
  FileText,
  Icon as LucideIcon,
  Component,
  Square,
} from "lucide-react";

// Tipi validi di tag HTML in JSX
export type HtmlTag = keyof JSX.IntrinsicElements;

type HtmlTagInfo = {
  tag: HtmlTag;
  label: string;
  icon: typeof LucideIcon;
};

// Mappa di icone associate manualmente ai tag
const tagIconMap: Partial<Record<HtmlTag, typeof LucideIcon>> = {
  div: PanelTop,
  p: Text,
  h1: Heading,
  h2: Heading,
  h3: Heading,
  img: Image,
  video: Video,
  ul: List,
  ol: ListOrdered,
  section: Layout,
  article: FileText,
  blockquote: Quote,
  table: Table,
  code: Code,
  button: SquareMousePointer,
  input: ArrowDown,
  span: Text,
  nav: Component,
  header: PanelTop,
  footer: PanelTop,
  label: BadgeInfo,
  textarea: Text,
  form: Square,
};

// Lista completa dei tag che vuoi supportare
const htmlTags: HtmlTag[] = [
  "div", "span", "p", "h1", "h2", "h3", "h4", "h5", "h6",
  "ul", "ol", "li", "img", "video", "audio", "section", "article", "header",
  "footer", "nav", "main", "aside", "button", "input", "textarea", "label",
  "form", "table", "thead", "tbody", "tr", "td", "th", "code", "pre", "blockquote"
];

export const htmlTagItems: HtmlTagInfo[] = htmlTags.map(tag => ({
  tag,
  label: tag.toUpperCase(),
  icon: tagIconMap[tag] ?? Component, // fallback a un'icona generica
}));
