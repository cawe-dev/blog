interface IChangelogItem {
    label: string
    date: Date
    type: string
    formattedDate?: string
}

interface IChangelogs {
    ChangelogItem: IChangelogItem[]
}

export type { IChangelogItem, IChangelogs }