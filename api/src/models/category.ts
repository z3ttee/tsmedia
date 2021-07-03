import { Table, Model, Column, DataType, BelongsTo, ForeignKey, PrimaryKey } from 'sequelize-typescript'
import config from '../config/config'

export interface IInvite {
    code?: string;
    expiresAt?: Date;
    maxUses?: number;
    inviterId?: string;
}

@Table({
    modelName: 'category',
    tableName: config.mysql.prefix + "categories",
    timestamps: true
})
export class Invite extends Model implements IInvite {

    @PrimaryKey
    @Column({ type: DataType.UUID, defaultValue: DataType.UUIDV4 })
    public uuid: string

    @Column({type: DataType.DATE, defaultValue: () => new Date(Date.now() + (1000*60*60*24*7)) })
    public expiresAt: Date;

    @Column({ type: DataType.INTEGER, defaultValue: 0 })
    public uses: number

    @Column({ type: DataType.INTEGER, defaultValue: -1 })
    public maxUses: number

    /*@Column({ type: DataType.UUID, allowNull: true })
    @BelongsTo(() => Member, { as: "inviter", onDelete: "CASCADE" })
    @ForeignKey(() => Member)
    public inviterId: string

    public inviter?: Member;*/

    /**
     * Check if invite is expired
     */
    public isExpired(): Boolean {
        let expiresAt = new Date(this.expiresAt).getTime()
        let currentTimeMillis = Date.now()
        return expiresAt <= currentTimeMillis
    }

    /**
     * Static method for checking integrity of invite code
     * @param inviteUUID 
     */
    static async isInviteValid(inviteUUID: string): Promise<Boolean> {

        let invite = await Invite.findOne({ where: {uuid: inviteUUID }})
        if(!invite) {
            return false
        }

        let isExpired = invite.isExpired()

        // Delete invite asynchronously
        if(isExpired) {
            Invite.destroy({ where: { uuid: inviteUUID }})
        }

        return !isExpired
    }

    
}