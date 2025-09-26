import React from "react";
import { Button } from "@/components/ui/button";
import {
  Popover,
  PopoverTrigger,
  PopoverContent,
} from "@/components/ui/popover";
import {
  Command,
  CommandInput,
  CommandList,
  CommandEmpty,
  CommandGroup,
  CommandItem,
} from "@/Components/ui/command";
import { Status } from "@types/content/pages/pagesType";

export const PageSettings = ({ status, setStatus }: 
  { status: Status, setStatus: (newStatus: Status) => void }) => {
  const statusOptions = [Status.Draft, Status.Published, Status.Trashed];
  const [open, setOpen] = React.useState(false);
  return (
    <Popover open={open} onOpenChange={setOpen}>
      <PopoverTrigger asChild>
        <Button variant="default">Status: {status}</Button>
      </PopoverTrigger>
      <PopoverContent className="w-48 p-0">
        <Command>
          <CommandList>
            <CommandGroup heading="Status">
              {statusOptions.map((option: Status) => (
                <CommandItem
                  key={option}
                  onSelect={() => {
                    setStatus(option);
                    setOpen(false);
                  }}
                >
                  {option}
                </CommandItem>
              ))}
            </CommandGroup>
          </CommandList>
        </Command>
      </PopoverContent>
    </Popover>
  );
};
