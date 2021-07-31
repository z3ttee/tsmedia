import { Table, Model, Column, DataType, BelongsTo, ForeignKey, PrimaryKey } from 'sequelize-typescript'
import config from '../config/config'

export interface ICategory {
    title: string;
    description: string;
}

@Table({
    modelName: 'category',
    tableName: config.mysql.prefix + "categories",
    timestamps: true
})
export class Category extends Model implements ICategory {

    @PrimaryKey
    @Column({ type: DataType.UUID, defaultValue: DataType.UUIDV4 })
    public uuid: string

    @Column({type: DataType.STRING, allowNull: false })
    public title: string;

    @Column({type: DataType.STRING, allowNull: false })
    public description: string;
}